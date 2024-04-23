import express, { Request, Response } from "express";
import ClientAdmFacadeFactory from "../../../modules/client-adm/factory/client-adm.facade.factory";

export const clientRoute = express.Router();

clientRoute.post("/", async (req: Request, res: Response) => {
    const clientFacade = ClientAdmFacadeFactory.create();

    const clientDto = {
        name: req.body.name,
        email: req.body.email,
        document: req.body.document,
        address: req.body.address
    }

    try {
        const output = await clientFacade.add(clientDto);
        res.send(output);
    } catch (err) {
        console.log(err)
        res.status(500).send(err)
    }
});