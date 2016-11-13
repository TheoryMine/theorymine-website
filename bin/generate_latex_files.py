#!/usr/bin/python
r"""Generate Latex Files for a named theorem.
"""

import datetime
import json
import os
import re
import requests
import subprocess
import sys

from lib import cprint

THIS_SCRIPT_DIR = os.path.dirname(__file__)
print('This script: %s' % THIS_SCRIPT_DIR)

CUR_DIR = os.getcwd()
print('cur directory: %s' % CUR_DIR)

SHARED_DIR=os.path.join(THIS_SCRIPT_DIR, '../', 'generated_certificates')

TEMPLATES_DIR = os.path.join(THIS_SCRIPT_DIR, '../', 'latex_templates')

TEMPLATE_FILES = [
    'brouchure_template.tex',
    'certificate_template.tex',
    'thm_template.tex',
    'thy_template.tex',
  ]

# with open("latex_template/brochure_template.tex", "wt") as fout:
#     with open("Stud.txt", "rt") as fin:
#         for line in fin:
#             fout.write(line.replace('A', 'Orange'))

def create_latex_file_from_template(template_filename, out_dirpath, subst):
  template_filepath = os.path.join(TEMPLATES_DIR, template_filename)
  out_filename = re.sub(r'_template', r'', template_filename)
  out_filepath = os.path.join(out_dirpath, out_filename)
  cprint.green('{0} --> {1}'.format(template_filepath, out_filepath))

  with open(template_filepath, "r") as fin:
    with open(out_filepath, "w") as fout:
      for line in fin:
        for key in subst.keys():
          line = line.replace(r'{{{' + key + r'}}}', subst[key])
        fout.write(line)

  return out_filename


def run(cmd):
  output = None
  try:
    output = subprocess.check_output(cmd)
  except subprocess.CalledProcessError:
    print('FAILED: {0}'.format(' '.join(cmd)))
    sys.exit(1)
  return output


def make_pdf(out_dirpath, out_filename):
  os.chdir(out_dirpath)
  cprint.green('cd %s' % out_dirpath)
  cmd = ['pdflatex', '-interaction', 'nonstopmode', '-output-format',
         'pdf', out_filename]
  output = run(cmd)
  output = run(cmd)
  output = run(cmd)
  lines = output.split('\n')
  for line in lines:
    print('LATEX: %s' % line);
  os.chdir(CUR_DIR)


def make_jpgs(out_dirpath):
  os.chdir(out_dirpath)
  cmd = ['convert', '-density', '400',
         'certificate.pdf', 'certificate_image.jpg']
  run(cmd)
  cmd = ['convert', '-gravity', 'South', '-chop', '0x1000', '-density', '400',
         'thy.pdf', 'thy.jpg']
  run(cmd)
  cmd = ['convert', '-gravity', 'South', '-chop', '0x4000', '-density', '400',
         'thm.pdf', 'thm.jpg']
  run(cmd)
  os.chdir(CUR_DIR)


def prepare_subst(named_theorem_subst):
  today = datetime.date.today()
  named_theorem_subst['date'] = today.strftime('%d %b %Y')

  theorem_lhs = named_theorem_subst['theorem_lhs']
  theorem_rhs = named_theorem_subst['theorem_rhs']
  thm_len = len(theorem_lhs + theorem_rhs)
  print('len(thm_lhs + thm_rhs)={0}'.format(thm_len))
  if thm_len < 90:
    named_theorem_subst['theorem'] = (
       '$$' + theorem_lhs + ' = ' + theorem_rhs + '$$')
  elif len(theorem_lhs) > len(theorem_rhs):
    named_theorem_subst['theorem'] = (
       '$$' + theorem_lhs + '$$ \n $$ = ' + theorem_rhs + '$$')
  else:
    named_theorem_subst['theorem'] = (
       '$$' + theorem_lhs + ' = $$ \n $$' + theorem_rhs + '$$')


# Without the faked user agent, the web-server gives a request not allowed.
FAKE_USER_AGENT = (
  "Mozilla/5.0 " +
  "(Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 " +
  "(KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36})");


def main(argv):
  cert_id = argv[1]
  cprint.green('certificate id: %s' % cert_id)

  out_dirpath = os.path.join(SHARED_DIR, cert_id)

  if not os.path.exists(out_dirpath):
    os.makedirs(out_dirpath)

  params = {'cid': cert_id}
  response = requests.get('http://theorymine.co.uk/api_latex_as_json.php',
                          headers={"User-Agent": FAKE_USER_AGENT},
                          params=params)
  response.raise_for_status()
  named_theorem_subst = response.json()
  print(json.dumps(named_theorem_subst, indent=2, sort_keys=True))

  prepare_subst(named_theorem_subst)
  print(json.dumps(named_theorem_subst, indent=2, sort_keys=True))

  for template_filename in TEMPLATE_FILES:
    out_filename = create_latex_file_from_template(
        template_filename, out_dirpath, named_theorem_subst)
    # make_pdf(out_dirpath, out_filename)

  # make_jpgs(out_dirpath)


if __name__ == '__main__':
  main(sys.argv)
