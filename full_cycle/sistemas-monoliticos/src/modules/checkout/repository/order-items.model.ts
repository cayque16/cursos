import { BelongsTo, Column, ForeignKey, Model, PrimaryKey, Table } from "sequelize-typescript";
import ProductModel from "./product.model";
import OrderModel from "./order.model";

@Table({
    tableName: "order_items",
    timestamps: false
})
export default class OrderItemsModel extends Model {
    @PrimaryKey
    @Column({ allowNull: false })
    id: string;

    @ForeignKey(() => ProductModel)
    @Column({ allowNull: false})
    idProduct: string;

    @BelongsTo(() => ProductModel)
    product: ProductModel;

    @ForeignKey(() => OrderModel)
    idOrder: string;
}