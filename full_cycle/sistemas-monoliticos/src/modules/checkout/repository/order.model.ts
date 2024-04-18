import { BelongsTo, Column, ForeignKey, HasMany, HasOne, Model, PrimaryKey, Table } from "sequelize-typescript";
import ClientModel from "./client.model";
import ProductModel from "./product.model";
import OrderItemsModel from "./order-items.model";

@Table({
    tableName: 'order',
    timestamps: false
})
export default class OrderModel extends Model {
    @PrimaryKey
    @Column({ allowNull: false })
    id: string;

    @ForeignKey(() => ClientModel)
    @Column({ allowNull: false })
    idClient: string;

    @BelongsTo(() => ClientModel)
    client: ClientModel;

    @HasMany(() => OrderItemsModel)
    items: OrderItemsModel[];

    @Column({ allowNull: false })
    status: string;
}