import express, { Express } from "express";
import { productRoute } from "./routes/product.route";
import { clientRoute } from "./routes/client.route";
import { checkoutRoute } from "./routes/checkout.route";

export const app: Express = express();
app.use(express.json());
app.use("/products", productRoute);
app.use("/clients", clientRoute);
app.use("/checkout", checkoutRoute);
