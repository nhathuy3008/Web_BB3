import { Routes, Route, Navigate } from "react-router-dom";
import Category from "./components/Category/category"
const AppRouter = () => {
    return (
        <Routes>
            <Route path="/" element={<Category />} />
        </Routes>
    );

};
export default AppRouter;