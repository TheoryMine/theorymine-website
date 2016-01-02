#/!bin/bash
set -e

CID=$1

if [ $1 ]
then
	echo "Creating certificates/latex/images for theorem certificate id: $1"
else
	echo "Please provide this command with certificate-id as the first argument."
	echo "e.g. 4603cc0cdff7f33804335c762fb323da5234"
	exit 1
fi

CODE_LOCATION="$(cd "$(dirname $0)"; pwd)";
echo "Running in: $CODE_LOCATION"
TMP_LOCATION="${CODE_LOCATION}/generated_certificates/$CID"
mkdir -p ${TMP_LOCATION}
cd ${TMP_LOCATION}
LOGFILE="errorcheck.log"

#Copy images for latex
echo "Copying images necessary for latex" > $LOGFILE
/bin/cp -r "${CODE_LOCATION}/certificates/images" ${TMP_LOCATION}
echo "Done copying images necessary for latex"

#Certificate
echo "Creating certificate"
curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=certificate" http://theorymine.co.uk/?go=latex > "certificate.tex"


pdflatex -interaction nonstopmode -output-format pdf certificate.tex
pdflatex -interaction nonstopmode -output-format pdf certificate.tex
pdflatex -interaction nonstopmode -output-format pdf certificate.tex

echo "Done creating certificate"

#theory image

echo "Creating theory"
curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=theory"  http://theorymine.co.uk/?go=latex > "thy.tex"

pdflatex -interaction nonstopmode -output-format pdf "thy.tex"
pdflatex -interaction nonstopmode -output-format pdf "thy.tex"
pdflatex -interaction nonstopmode -output-format pdf "thy.tex"

convert -gravity South -chop 0x1000 -density 400 "thy.pdf" "thy.jpg"

echo "Done creating theory"

#theorem image


echo "Creating theorem"
curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=theorem"  http://theorymine.co.uk/?go=latex > "thm.tex"

pdflatex -interaction nonstopmode -output-format pdf "thm.tex"
pdflatex -interaction nonstopmode -output-format pdf "thm.tex"
pdflatex -interaction nonstopmode -output-format pdf "thm.tex"

convert -gravity South -chop 0x4000 -density 400 "thm.pdf" "thm.jpg"

echo "Done creating theorem"

#certificate image

echo "Creating certificate image"
curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=certificate_image"  http://theorymine.co.uk/?go=latex > "c_image.tex"

pdflatex -interaction nonstopmode -output-format pdf "c_image.tex"
pdflatex -interaction nonstopmode -output-format pdf "c_image.tex"
pdflatex -interaction nonstopmode -output-format pdf "c_image.tex"

convert -density 400 "c_image.pdf" "certificate_image.jpg"

echo "Done creating certificate image"

#brouchure

echo "Creating brouchure"

curl --data "cid=${CID}&pass=vtp:ca3nyH9ewgHR&dockind=brouchure"  http://theorymine.co.uk/?go=latex > "brouchure.tex"

pdflatex -interaction nonstopmode -output-format pdf  "brouchure.tex"
pdflatex -interaction nonstopmode -output-format pdf  "brouchure.tex"
pdflatex -interaction nonstopmode -output-format pdf  "brouchure.tex"


echo "Done creating brouchure"
