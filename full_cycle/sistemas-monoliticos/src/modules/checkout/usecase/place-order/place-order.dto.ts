export interface PlaceOrderInputDto {
  clientId: string;
  products: {
    productId: string;
  }[];
}

export interface PlaceOrderOutputDto {
  id: string;
  idInvoice: string;
  total: number;
  products: {
    productId: string;
  }[];
}
