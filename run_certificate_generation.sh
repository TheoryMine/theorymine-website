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
$CODE_LOCATION/bin/prepare_certificate_latex.sh $CID
$CODE_LOCATION/bin/run_latex_for_certificate.sh $CID

echo "Certificate created."
