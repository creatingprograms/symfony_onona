.RECIPEPREFIX := +
GULP ?= node_modules/.bin/gulp

gulp:
+ $(GULP)

css:
+ $(GULP) build.css

newCss:
+ $(GULP) newCss

js:
+ $(GULP) build.js


