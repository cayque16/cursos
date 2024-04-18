import Id from "../../@shared/domain/value-object/id.value-object";
import Client from "../domain/client.entity";
import Order from "../domain/order.entity";
import Product from "../domain/product.entity";
import CheckoutGateway from "../gateway/checkout.gateway";
import ClientModel from "./client.model";
import OrderItemsModel from "./order-items.model";
import OrderModel from "./order.model";
import ProductModel from "./product.model";

export default class OrderRepository implements CheckoutGateway {
    async addOrder(order: Order): Promise<void> {
        await OrderModel.create({
            id: order.id.id,
            idClient: order.client.id.id,
            status: order.status
        })

        order.products.forEach(element => {
            ProductModel.create({
                id: element.id.id,
                name: element.name,
                description: element.description,
                salesPrice: element.salesPrice,
            });
        });
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
                    model: ClientModel,
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
            const product = await ProductModel.findOne({where: {id: element.idProduct}})
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