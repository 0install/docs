#!/bin/bash -eu
if [[ ! -z $(git status -uno --porcelain) ]]; then
  echo Uncommitted changed. Commit them first!
  exit 1
fi

branch=$(git rev-parse --abbrev-ref HEAD)
if [ $branch != "master" ]; then
  if [ $branch != "github" ]; then
    echo "Not on master branch - can't push"
    exit 1
  fi
fi

rm -rf github-tmp
git clone . github-tmp -b $branch
cd github-tmp
make
for f in $(find -type l);do cp --remove-destination $(realpath $f) $f; git add $f; done;
git add *.html *.php sitemap.txt
git rm index.xml	# Make sure we don't use this for the index page
git rm deploy-to-github-pages.sh	# Avoid accidents running it from github-tmp
(cd .. && git push github $branch)
git commit -m "Deploy to GitHub Pages"
git push --force "git@github.com:0install/web-site.git" HEAD:gh-pages
