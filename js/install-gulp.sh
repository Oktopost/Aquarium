#!/usr/bin/env bash
sudo apt-get install nodejs npm
sudo ln -s "$(which nodejs)" /usr/bin/node

sudo npm install --global gulp-cli
sudo npm install --save-dev gulp-uti

cd GulpCompiler
npm install