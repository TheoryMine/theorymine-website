# to be run as root to setup database for a user called kenyersel


echo "dropping old kenyersel user and db"

sudo -u postgres dropdb kenyersel 
sudo -u postgres dropuser kenyersel

echo "creating new kenyersel user and db"
echo "enter password for kenyersel database user:"
sudo -u postgres createuser -P -S -D -A -R kenyersel
sudo -u postgres createdb -O kenyersel kenyersel 
