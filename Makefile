# ===========================
# Default: help section
# ===========================

info: intro do-show-commands

intro:
	@echo ""
	@echo "   _____                       _           _ _   "
	@echo "  / ____|                     | |         | | |  "
	@echo " | (___  _   _ _ __   ___ _ __| |__   ___ | | |_ "
	@echo "  \___ \| | | | '_ \ / _ \ '__| '_ \ / _ \| | __|"
	@echo "  ____) | |_| | |_) |  __/ |  | |_) | (_) | | |_ "
	@echo " |_____/ \__,_| .__/ \___|_|  |_.__/ \___/|_|\__|"
	@echo "              | | "
	@echo "              |_|"
	@echo ""

# ===========================
# Main commands
# ===========================

install: intro do-composer-install do-assets-install

# Build assets
assets: intro do-assets-install

# Tests
tests: intro do-test-unit do-test-report
test-unit: intro do-test-unit

# Codestyle
pre-commit: intro do-lint-staged-files do-commit-intro
codestyle: intro do-cs-ecs
codestyle-fix: intro do-cs-ecs-fix

# ===========================
# Recipes
# ===========================

do-show-commands:
	@echo "\n=== Make commands ===\n"
	@echo "Project commands:"
	@echo "    make install                   Make the project ready for development."
	@echo "\nBuild assets:"
	@echo "    make assets                    Install dependencies and compile the assets."
	@echo "\nTests:"
	@echo "    make tests                     Run tests."
	@echo "    make test-unit                 Run unit tests."

do-composer-install:
	@echo "\n=== Installing composer dependencies ===\n"\
	COMPOSER_MEMORY_LIMIT=-1 composer install

do-assets-install:
	@echo "\n=== Installing npm dependencies ===\n"
	npm install

do-test-unit:
	@echo "\n=== Running unit tests ===\n"
	vendor/bin/phpunit tests/Unit

do-test-report:
	@echo "\n=== Click the link below to see the test coverage report ===\n"
	@echo "report/index.html"

do-commit-intro:
	@echo "\n=== Let's ship it! ===\n"

do-lint-staged-files:
	@node_modules/.bin/lint-staged

do-cs-ecs:
	./vendor/bin/ecs check --config=easy-coding-standard.yml

do-cs-ecs-fix:
	./vendor/bin/ecs check --fix --config=easy-coding-standard.yml
