import EventHandlerInterface from "../../../@shared/event/event-handler.interface";
import CustomerChangeAddress from "../curstomer-change-address.event";

export default class EnviaConsoleLogHandler
    implements EventHandlerInterface<CustomerChangeAddress>
{
    handle(event: CustomerChangeAddress): void {
        const id = event.eventData.id;
        const nome = event.eventData.name;
        const endereco = event.eventData.address;

        console.log(`Endereço do cliente: ${id}, ${nome} alterado para: ${endereco}`);
    }
}