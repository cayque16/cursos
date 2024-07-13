import { useDispatch } from "react-redux";
import { keycloak } from "../keycloakConfig";
import { setAuthenticated, setToken, setUserDetails } from "../features/auth/authSlice";
import { useEffect } from "react";

export const KeycloakProvider = ({
    children,
}: {
    children: React.ReactNode;
}) => {
    const dispatch = useDispatch();

    useEffect(() => {
        const initKeycloak = async () => {
            try {
                const authenticated = await keycloak.init({
                    onLoad: "login-required",
                });
                console.log(authenticated);
                if (authenticated) {
                    dispatch(setAuthenticated(true));
                    dispatch(setToken(keycloak.token));
                    const userInfo = await keycloak.loadUserInfo();
                    dispatch(setUserDetails(userInfo));
                } else {
                    dispatch(setAuthenticated(false));
                }
            } catch (error) {
                console.log("Keycloak initialization error: ", error);
                dispatch(setAuthenticated(false));
            }
        };

        const updateToken = (refresh = false) => {
            if (refresh) {
                keycloak.updateToken(70).then((refreshed) => {
                    if (refreshed) {
                        dispatch(setToken(keycloak.token));
                    }
                });
            }
        };

        keycloak.onTokenExpired = () => {
            return updateToken(true);
        }

        initKeycloak();
    }, [dispatch]);

    return <>{children}</>;
};
