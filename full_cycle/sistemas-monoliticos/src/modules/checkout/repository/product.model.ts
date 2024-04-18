import { Column, ForeignKey, HasMany, Model, PrimaryKey, Table } from "sequelize-typescript";
import OrderModel from "./order.model";
import OrderItemsModel from "./order-items.model";

@Table({
    tableName: "products",
    timestamps: false
})
export default class ProductOrderModel extends Model {
    @PrimaryKey
    @Column({ allowNull: false})
    id: string;

    @Column({ allowNull: false })
    name: string;

    @Column({ allowNull: false })
    description: string;

    @Column({ allowNull: false })
    salesPrice: number;
}