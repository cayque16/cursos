import { DataTypes, Sequelize } from 'sequelize';
import { MigrationFn } from 'umzug';

export const up: MigrationFn<Sequelize> = async ({ context: sequelize }) => {
    await sequelize.getQueryInterface().createTable('invoice_items', {
        id: {
            type: DataTypes.STRING(255),
            primaryKey: true,
            allowNull: false
        },
        idInvoice: {
            type: DataTypes.STRING(255),
            allowNull: false,
        },
        name: {
            type: DataTypes.STRING(255),
            allowNull: false,
        },
        price: {
            type: DataTypes.NUMBER,
            allowNull: true,
        },
    })
    await sequelize.getQueryInterface().addConstraint('invoice_items', {
        fields: ['idInvoice'],
        type: 'foreign key',
        name: 'fk_id_invoice',
        references: {
            table: 'invoice',
            field: 'id'
        },
        onDelete: 'no action',
        onUpdate: 'no action'
    })
};
export const down: MigrationFn<Sequelize> = async ({ context: sequelize }) => {
    await sequelize.getQueryInterface().dropTable('invoice_items');
};
