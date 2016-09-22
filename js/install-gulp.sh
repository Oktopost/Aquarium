#!/usr/bin/env bash
sudo apt-get install nodejs npm
sudo ln -fs "$(which nodejs)" /usr/bin/node

sudo npm install --global gulp-cli
sudo npm install --save-dev gulp-util

cd GulpCompiler
npm install