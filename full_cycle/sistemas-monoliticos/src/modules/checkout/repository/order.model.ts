import { BelongsTo, Column, ForeignKey, HasMany, HasOne, Model, PrimaryKey, Table } from "sequelize-typescript";
import ClientOrderModel from "./client.model";
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

    @ForeignKey(() => ClientOrderModel)
    @Column({ allowNull: false })
    idClient: string;

    @BelongsTo(() => ClientOrderModel)
    client: ClientOrderModel;

    @HasMany(() => OrderItemsModel)
    items: OrderItemsModel[];

    @Column({ allowNull: false })
    status: string;
}