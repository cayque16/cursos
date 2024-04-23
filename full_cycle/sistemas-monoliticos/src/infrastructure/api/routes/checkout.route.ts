import express, { Request, Response } from "express";
import CheckoutFacadeFactory from "../../../modules/checkout/factory/checkout.facade.factory";

export const checkoutRoute = express.Router();

checkoutRoute.post("/", async (req: Request, res: Response) => {
    const checkoutFacade = CheckoutFacadeFactory.create();

    const checkoutDto = {
        clientId: req.body.clientId,
        products: req.body.products
    }

    try {
        const output = await checkoutFacade.execute(checkoutDto);
        res.send(output);
    } catch (err) {
        console.log(err);
        res.status(500).send(err);
    }
});