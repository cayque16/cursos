import axios, { AxiosInstance } from "axios";

const clienteHttp: AxiosInstance = axios.create({
  baseURL: "http://192.168.2.17:3000/",
});

export default clienteHttp;
