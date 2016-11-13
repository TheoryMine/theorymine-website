#/!bin/bash
set -e

CID=$1

if [ $1 ]
then
  echo "Running latex/image-magic for theorem certificate id: $1"
else
  echo "Please provide this command with certificate-id as the first argument."
  echo "e.g. 4603cc0cdff7f33804335c762fb323da5234"
  exit 1
fi

CODE_LOCATION="$(cd "$(dirname $0)/../"; pwd)";
echo "Running in: $CODE_LOCATION"
TMP_LOCATION="${CODE_LOCATION}/generated_certificates/$CID"
mkdir -p ${TMP_LOCATION}
cd ${TMP_LOCATION}
LOGFILE="errorcheck.log"

#Certificate
pdflatex -interaction nonstopmode -output-format pdf "certificate.tex"
pdflatex -interaction nonstopmode -output-format pdf "certificate.tex"
pdflatex -interaction nonstopmode -output-format pdf "certificate.tex"
convert -density 400 "certificate.pdf" "certificate_image.jpg"
echo "Done creating certificate"

#theory image
pdflatex -interaction nonstopmode -output-format pdf "thy.tex"
pdflatex -interaction nonstopmode -output-format pdf "thy.tex"
pdflatex -interaction nonstopmode -output-format pdf "thy.tex"
convert -gravity South -chop 0x1000 -density 400 "thy.pdf" "thy.jpg"
echo "Done creating theory"

#theorem image
pdflatex -interaction nonstopmode -output-format pdf "thm.tex"
pdflatex -interaction nonstopmode -output-format pdf "thm.tex"
pdflatex -interaction nonstopmode -output-format pdf "thm.tex"
convert -gravity South -chop 0x4000 -density 400 "thm.pdf" "thm.jpg"
echo "Done creating theorem"

#brouchure
pdflatex -interaction nonstopmode -output-format pdf  "brouchure.tex"
pdflatex -interaction nonstopmode -output-format pdf  "brouchure.tex"
pdflatex -interaction nonstopmode -output-format pdf  "brouchure.tex"
echo "Done creating brouchure"
