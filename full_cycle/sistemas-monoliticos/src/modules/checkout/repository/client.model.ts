import { Column, Model, PrimaryKey, Table } from "sequelize-typescript";

@Table({
    tableName: "client",
    timestamps: false
})
export default class ClientOrderModel extends Model {
    @PrimaryKey
    @Column({ allowNull: false })
    id: string;

    @Column({ allowNull: false })
    name: string;

    @Column({ allowNull: false })
    email: string;

    @Column({ allowNull: false })
    address: string;
}