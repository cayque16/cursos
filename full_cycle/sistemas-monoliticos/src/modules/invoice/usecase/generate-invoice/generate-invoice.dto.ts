import InvoiceItems from "../../domain/invoice-items.entity";

export interface GenerateInvoiceUseCaseInputDto {
    name: string;
    document: string;
    street: string;
    number: string;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
    items: InvoiceItems[];
}
  
  export interface GenerateInvoiceUseCaseOutputDto {
    id: string;
    name: string;
    document: string;
    street: string;
    number: string;
    complement: string;
    city: string;
    state: string;
    zipCode: string;
    items: InvoiceItems[];
    total: number;
}