import Order from "../../../../domain/checkout/entity/order";
import OrderItem from "../../../../domain/checkout/entity/order_item";
import OrderRepositoryInterface from "../../../../domain/checkout/repository/order-repository.interface";
import OrderItemModel from "./order-item.model";
import OrderModel from "./order.model";

export default class OrderRepository implements OrderRepositoryInterface{
  async create(entity: Order): Promise<void> {
    await OrderModel.create(
      {
        id: entity.id,
        customer_id: entity.customerId,
        total: entity.total(),
        items: entity.items.map((item) => ({
          id: item.id,
          name: item.name,
          price: item.price,
          product_id: item.productId,
          quantity: item.quantity,
        })),
      },
      {
        include: [{ model: OrderItemModel }],
      }
    );
  }

  async update(entity: Order): Promise<void> {
    await OrderModel.update(
      {
        customer_id: entity.customerId,
      },
      {
        where: {
          id: entity.id,
        },
      }
    );
  }

  async find(id: string): Promise<Order> {
    let orderModel, orderItensModel;
    try {
      orderModel = await OrderModel.findOne({
        where: {
          id,
        },
        rejectOnEmpty:true,
      });
      orderItensModel = await OrderItemModel.findAll({
        where: {
          order_id: id,
        }
      });
    } catch (error) {
      throw new Error("Order not found")
    }

    const items = orderItensModel.map((orderItensModel) => {
      let item = new OrderItem(
        orderItensModel.id,
        orderItensModel.name,
        orderItensModel.price,
        orderItensModel.product_id,
        orderItensModel.quantity
      );
      return item;
    });
    
    return new Order(id, orderModel.customer_id, items);
  }

  async findAll(): Promise<Order[]> {
    const ordersModels = await OrderModel.findAll();
    const orders = [];

    for (const orderModel of ordersModels) {
      const order = await this.find(orderModel.id);
      orders.push(order);
    }
    
    return orders;
  }
}
