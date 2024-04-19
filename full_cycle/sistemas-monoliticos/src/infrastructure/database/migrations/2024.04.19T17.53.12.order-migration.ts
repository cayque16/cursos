import { DataTypes, Sequelize } from 'sequelize';
import { MigrationFn } from 'umzug';

export const up: MigrationFn<Sequelize> = async ({ context: sequelize }) => {
    await sequelize.getQueryInterface().createTable('order', {
        id: {
            type: DataTypes.STRING(255),
            primaryKey: true,
            allowNull: false
        },
        idClient: {
            type: DataTypes.STRING(255),
            allowNull: false,
        },
        status: {
            type: DataTypes.STRING(255),
            allowNull: false,
        },
    })
    await sequelize.getQueryInterface().addConstraint('order', {
        fields: ['idClient'],
        type: 'foreign key',
        name: 'fk_id_client',
        references: { 
            table: 'client',
            field: 'id'
        },
        onDelete: 'no action',
        onUpdate: 'no action'
    })
};
export const down: MigrationFn<Sequelize> = async ({ context: sequelize }) => {
    await sequelize.getQueryInterface().dropTable('order');
};
