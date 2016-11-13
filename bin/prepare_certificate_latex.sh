#/!bin/bash
set -e

CID=$1

if [ $1 ]
then
  echo "Downloading/copying latex/images for theorem certificate id: $1"
else
  echo "Please provide this command with certificate-id as the first argument."
  echo "e.g. 4603cc0cdff7f33804335c762fb323da5234"
  exit 1
fi

CODE_LOCATION="$(cd "$(dirname $0)/../"; pwd)";
echo "Running in: $CODE_LOCATION"

CODE_LOCATION="$(cd "$(dirname $0)/../"; pwd)";
echo "Running in: $CODE_LOCATION"
TMP_LOCATION="${CODE_LOCATION}/generated_certificates/$CID"
mkdir -p ${TMP_LOCATION}
cd ${TMP_LOCATION}
LOGFILE="errorcheck.log"

#Copy images for latex
echo "Copying images necessary for latex" > $LOGFILE
/bin/cp -r "${CODE_LOCATION}/latex_templates/images" ${TMP_LOCATION}
/bin/cp -r "${CODE_LOCATION}/latex_templates/fonts" ${TMP_LOCATION}
echo "Done copying images necessary for latex"

${CODE_LOCATION}/bin/generate_latex_files.py ${CID}

# #Certificate
# echo "Downloading certificate.tex ..."
# curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=certificate" http://theorymine.co.uk/?go=latex > "certificate.tex"

# #theory image

# echo "Downloading thy.tex ..."
# curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=theory"  http://theorymine.co.uk/?go=latex > "thy.tex"

# #theorem image

# echo "Downloading theorem.tex ..."
# curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=theorem"  http://theorymine.co.uk/?go=latex > "thm.tex"

# #certificate image
# echo "Downloading c_image.tex ..."
# curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=certificate_image"  http://theorymine.co.uk/?go=latex > "c_image.tex"

# #brouchure

# echo "Downloading brouchure.tex ..."
# curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=brouchure"  http://theorymine.co.uk/?go=latex > "brouchure.tex"

# echo "Done Downloaded all source files."
