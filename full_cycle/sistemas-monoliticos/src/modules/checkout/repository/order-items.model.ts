import { BelongsTo, Column, ForeignKey, Model, PrimaryKey, Table } from "sequelize-typescript";
import ProductOrderModel from "./product.model";
import OrderModel from "./order.model";

@Table({
    tableName: "order_items",
    timestamps: false
})
export default class OrderItemsModel extends Model {
    @PrimaryKey
    @Column({ allowNull: false })
    id: string;

    @ForeignKey(() => ProductOrderModel)
    @Column({ allowNull: false})
    idProduct: string;

    @BelongsTo(() => ProductOrderModel)
    product: ProductOrderModel;

    @ForeignKey(() => OrderModel)
    @Column({ allowNull: false})
    idOrder: string;
}