#!/bin/bash -e
#
# Publishes the release to S3 for hmlinter.

CURRENT_VERSION=$(node -p "require('./packages/eslint-config-humanmade/package.json').version")

echo "Creating archives of $CURRENT_VERSION for hmlinter"
test -d archives || mkdir archives

# Prepare phpcs standard
test -d phpcs-standard && rm -r phpcs-standard
mkdir phpcs-standard
echo '{"require": {"humanmade/coding-standards": "'$CURRENT_VERSION'"}}' > phpcs-standard/composer.json
composer install -d phpcs-standard
cp ruleset.xml phpcs-standard/
tar czvf "archives/phpcs-$CURRENT_VERSION.tar.gz" -C phpcs-standard --exclude '*/tests/*' --exclude '*/fixtures/*' ruleset.xml vendor/

# Prepare eslint
yarn install --cwd packages/eslint-config-humanmade
tar czvf "archives/eslint-$CURRENT_VERSION.tar.gz" -C packages/eslint-config-humanmade .eslintrc index.js package.json node_modules/

# Prepare stylelint
yarn install --cwd packages/stylelint-config
tar czvf "archives/stylelint-$CURRENT_VERSION.tar.gz" -C packages/stylelint-config .stylelintrc.json package.json node_modules/

read -p "Bump 'latest' to $CURRENT_VERSION [Y/n]? " choice
case "$choice" in
	y|Y|"")
		echo "Bumping 'latest' to $CURRENT_VERSION"
		cp "archives/eslint-$CURRENT_VERSION.tar.gz" "archives/eslint-latest.tar.gz"
		cp "archives/phpcs-$CURRENT_VERSION.tar.gz" "archives/phpcs-latest.tar.gz"
		cp "archives/stylelint-$CURRENT_VERSION.tar.gz" "archives/stylelint-latest.tar.gz"
		;;
	n|N )
		echo "Skipping 'latest'"
		;;
	* )
		echo "Invalid choice, exiting"
		exit 1
		;;
esac

echo "Publishing archives to S3..."
aws s3 sync --acl=public-read archives/ s3://hm-linter/standards/
