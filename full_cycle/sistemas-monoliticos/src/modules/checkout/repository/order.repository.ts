import { v4 as uuidv4 } from "uuid";
import Id from "../../@shared/domain/value-object/id.value-object";
import Client from "../domain/client.entity";
import Order from "../domain/order.entity";
import Product from "../domain/product.entity";
import CheckoutGateway from "../gateway/checkout.gateway";
import ClientOrderModel from "./client.model";
import OrderItemsModel from "./order-items.model";
import OrderModel from "./order.model";
import ProductOrderModel from "./product.model";

export default class OrderRepository implements CheckoutGateway {
    async addOrder(order: Order): Promise<void> {
        await OrderModel.create({
            id: order.id.id,
            idClient: order.client.id.id,
            status: order.status
        })

        for(const element of order.products) {
            await OrderItemsModel.create({
                id: uuidv4(),
                idProduct: element.id.id,
                idOrder: order.id.id
            });
        }
    }

    async findOrder(id: string): Promise<Order> {
        const order  = await OrderModel.findAll({
            where: {id},
            include: [
                {
                    model: OrderItemsModel,
                    required: true,
                },
                {
                    model: ClientOrderModel,
                    required: true,
                },
            ]
        });

        if (!order.length) {
            throw new Error(`Order with id ${id} not found`);
        }

        const client = new Client({
            id: new Id(order[0].client.id),
            name: order[0].client.name,
            address: order[0].client.address,
            email: order[0].client.email
        });

        let products: Product[]  = [];
        for(const element of order[0].items) {
            const product = await ProductOrderModel.findOne({where: {id: element.idProduct}})
            products.push(new Product({
                id: new Id(product.id),
                name: product.name,
                description: product.description,
                salesPrice: product.salesPrice
            }));
        }

        return new Order({
            id: new Id(order[0].id),
            client: client,
            products: products
        })
    }
}