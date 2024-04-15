import Address from "../../@shared/domain/value-object/address";
import InvoiceItems from "../domain/invoice-items.entity";
export interface FindInvoiceUseCaseInputDTO {
  id: string;
}

export interface FindInvoiceUseCaseOutputDTO {
  id: string;
  name: string;
  document: string;
  address: Address;
  items: InvoiceItems[];
  total: number;
  createdAt: Date;
}

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

export default interface InvoiceFacadeInterface {
  find(input: FindInvoiceUseCaseInputDTO): Promise<FindInvoiceUseCaseOutputDTO>;  
  generate(input: GenerateInvoiceUseCaseInputDto): Promise<GenerateInvoiceUseCaseOutputDto>;
}