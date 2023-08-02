import EventDispatcher from "../../../@shared/event/event-dispatcher";
import CustomerCreatedEvent from "../customer-created.event";
import EnviaConsoleLog1Handler from "./envia-console-log1-handler";

describe("Console log 1 tests", () => {
   it("should notify the event", () => {
    const eventDispatcher = new EventDispatcher();
    const eventHandler = new EnviaConsoleLog1Handler();
    const spyEventHandler = jest.spyOn(eventHandler, "handle");

    eventDispatcher.register("CustomerCreatedEvent", eventHandler);

    expect(
        eventDispatcher.getEventHandlers["CustomerCreatedEvent"][0]
    ).toMatchObject(eventHandler);

    const cusotmerCreatedEvent = new CustomerCreatedEvent({
        id: "231",
        name: "Fulano"
    });

    eventDispatcher.notify(cusotmerCreatedEvent);

    expect(spyEventHandler).toHaveBeenCalled();
   });
});