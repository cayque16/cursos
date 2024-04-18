import UseCaseInterface from "../../@shared/usecase/use-case.interface";
import CheckoutFacadeInterface, { CheckoutFacadeInputDto, CheckoutFacadeOutputDto } from "./checkout.facade.interface";

export default class CheckoutFacade implements CheckoutFacadeInterface {
    private checkoutUsecase: UseCaseInterface;

    constructor(checkoutUseCase: UseCaseInterface) {
        this.checkoutUsecase = checkoutUseCase;
    }

    execute(input: CheckoutFacadeInputDto): Promise<CheckoutFacadeOutputDto> {
        return this.checkoutUsecase.execute(input);
    }
}