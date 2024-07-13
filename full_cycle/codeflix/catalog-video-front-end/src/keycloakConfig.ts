import Keycloak from "keycloak-js";

const keycloakConfig = {
    url: "http://192.168.2.10:8081/auth",
    realm: "fullcycle",
    clientId: "admin-catalog-video",
};

export const keycloak = new Keycloak(keycloakConfig);
