#!/bin/bash -eux
if [[ ! -z $(git status -uno --porcelain) ]]; then
  echo Uncommitted changed. Commit them first!
  exit 1
fi

if [ $(git branch) != "master" ]; then
  echo "Not on master branch - can't push"
  exit 1
fi

rm -rf github-tmp
git clone . github-tmp
cd github-tmp
make
git add *.html *.php sitemap.txt
git commit -m "Deploy to GitHub Pages"
git push --force "git@github.com:0install/web-site.git" HEAD:gh-pages
