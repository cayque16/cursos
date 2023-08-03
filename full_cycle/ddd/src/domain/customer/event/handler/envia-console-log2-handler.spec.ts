import EventDispatcher from "../../../@shared/event/event-dispatcher";
import CustomerCreatedEvent from "../customer-created.event";
import EnviaConsoleLog2Handler from "./envia-console-log2-handler";

describe("Console log 2 tests", () => {
    it("should notify the event", () => {
        const eventDispatcher = new EventDispatcher();
        const eventHandler = new EnviaConsoleLog2Handler();
        const spyEventHandler = jest.spyOn(eventHandler, "handle");

        eventDispatcher.register("CustomerCreatedEvent", eventHandler);

        expect(
            eventDispatcher.getEventHandlers["CustomerCreatedEvent"][0]
        ).toMatchObject(eventHandler);

        const customerCreatedEvent = new CustomerCreatedEvent({
            id: "357",
            name: "Beltrano"
        });

        eventDispatcher.notify(customerCreatedEvent);

        expect(spyEventHandler).toHaveBeenCalled();
    });
});