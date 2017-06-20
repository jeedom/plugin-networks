PROGRESS_FILE=/tmp/dependancy_networks_in_progress
if [ ! -z $1 ]; then
	PROGRESS_FILE=$1
fi
touch ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo "Launch install of Networks dependancy"
apt-get update
echo 50 > ${PROGRESS_FILE}
apt-get install -y wakeonlan 
echo 75 > ${PROGRESS_FILE}
apt-get install -y etherwake 
echo 100 > ${PROGRESS_FILE}
echo "Everything is successfully installed!"
rm ${PROGRESS_FILE}
