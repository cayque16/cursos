import express, { Express } from "express";
import { Sequelize } from "sequelize-typescript";
import { ProductModel } from "../../modules/product-adm/repository/product.model";
import { productRoute } from "./routes/product.route";
import { clientRoute } from "./routes/client.route";
import { ClientModel } from "../../modules/client-adm/repository/client.model";
import InvoiceModel from "../../modules/invoice/repository/invoice.model";
import InvoiceItemsModel from "../../modules/invoice/repository/invoice-items.model";
import TransactionModel from "../../modules/payment/repository/transaction.model";
import { checkoutRoute } from "./routes/checkout.route";
import ClientOrderModel from "../../modules/checkout/repository/client.model";
import ProductOrderModel from "../../modules/checkout/repository/product.model";
import OrderItemsModel from "../../modules/checkout/repository/order-items.model";
import OrderModel from "../../modules/checkout/repository/order.model";

export const app: Express = express();
app.use(express.json());
app.use("/products", productRoute);
app.use("/clients", clientRoute);
app.use("/checkout", checkoutRoute);

export let sequelize: Sequelize;

async function setupDb() {
  sequelize = new Sequelize({
    dialect: "sqlite",
    storage: ":memory:",
    logging: false,
  });
  await sequelize.addModels([
    ProductModel,
    ClientModel,
    InvoiceModel,
    InvoiceItemsModel,
    TransactionModel,
    ClientOrderModel,
    ProductOrderModel,
    OrderItemsModel,
    OrderModel
  ]);
  await sequelize.sync();
}
setupDb();