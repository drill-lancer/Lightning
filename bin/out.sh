#!/bin/bash

cd /usr/local/bin
rm -rf ./mac-zip-windows/
git clone https://github.com/macyarounanoka/mac-zip-windows.git
mv ./mac-zip-windows/windowszip ./windowszip
rm -rf ./mac-zip-windows/
chmod +x windowszip
windowszip /Users/kurudrive/"Local Sites"/test/app/public/wp-content/themes/lightning/dist/lightning
pwd
