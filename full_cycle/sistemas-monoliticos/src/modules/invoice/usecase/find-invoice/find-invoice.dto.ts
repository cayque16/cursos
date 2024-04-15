import Address from "../../../@shared/domain/value-object/address";
import InvoiceItems from "../../domain/invoice-items.entity";

export interface FindInvoiceInputDto {
    id: string;
}

export interface FindInvoiceOutputDto {
    id: string;
    name: string;
    document: string;
    address: Address;
    items: InvoiceItems[];
    total: number;
    createdAt?: Date;
}