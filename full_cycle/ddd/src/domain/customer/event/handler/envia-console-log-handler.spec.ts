import EventDispatcher from "../../../@shared/event/event-dispatcher";
import CustomerChangeAddress from "../curstomer-change-address.event";
import EnviaConsoleLogHandler from "./envia-console-log-handler";

describe("Console log change address tests", () => {
    it("should notify event", () => {
        const eventDispatcher = new EventDispatcher();
        const eventHandler = new EnviaConsoleLogHandler();
        const spyEventHandler = jest.spyOn(eventHandler, "handle");

        eventDispatcher.register("CustomerChangeAddress", eventHandler);

        expect(
            eventDispatcher.getEventHandlers["CustomerChangeAddress"][0]
        ).toMatchObject(eventHandler);

        const customerChangeAddress = new CustomerChangeAddress({
            id: "432",
            name: "Ciclano",
            address: "Rua H, 159, 35.123-915, Cidade do Nada"
        });

        eventDispatcher.notify(customerChangeAddress);

        expect(spyEventHandler).toHaveBeenCalled();
    });
});