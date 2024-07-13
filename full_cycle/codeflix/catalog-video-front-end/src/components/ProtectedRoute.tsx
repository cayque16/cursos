import { useSelector } from "react-redux";
import { selectIsAuthenticated } from "../features/auth/authSlice";
import { Navigate } from "react-router-dom";

export const ProtectedRoute = ({ children }: { children: React.ReactNode }) => {
    const isAuthenticated = useSelector(selectIsAuthenticated);

    if (!isAuthenticated) {
        return <Navigate to="/login" />;
    }

    return <>children</>;
};
