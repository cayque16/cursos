./stack-node.sh -r ts-node/register/transpile-only ./src/infrastructure/database/migrations/config-migrations/migrator-cli.ts ${@}

# criar: ./migrate.sh create --name client-migration.ts --folder src/infrastructure/database/migrations/