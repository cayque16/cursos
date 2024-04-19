import { DataTypes, Sequelize } from 'sequelize';
import { MigrationFn } from 'umzug';

export const up: MigrationFn<Sequelize> = async ({context: sequelize}) => {
    await sequelize.getQueryInterface().createTable('order_items', {
        id: {
            type: DataTypes.STRING(255),
            primaryKey: true,
            allowNull: false
        },
        idProduct: {
            type: DataTypes.STRING(255),
            allowNull: false
        },
        idOrder: {
            type: DataTypes.STRING(255),
            allowNull: false
        },
    });
    await sequelize.getQueryInterface().addConstraint('order_items', {
        fields: ['idProduct'],
        type: 'foreign key',
        name: 'fk_id_product',
        references: { 
            table: 'products',
            field: 'id'
        },
        onDelete: 'no action',
        onUpdate: 'no action'
    });
    await sequelize.getQueryInterface().addConstraint('order_items', {
        fields: ['idOrder'],
        type: 'foreign key',
        name: 'fk_id_order',
        references: { 
            table: 'order',
            field: 'id'
        },
        onDelete: 'no action',
        onUpdate: 'no action'
    });
};
export const down: MigrationFn<Sequelize> = async ({context: sequelize}) => {
    await sequelize.getQueryInterface().dropTable('order_items');
};
