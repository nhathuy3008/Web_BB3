import { useState } from 'react'
import { BrowserRouter as Router, useLocation } from "react-router-dom";
import AppRouter from './AppRouter';
import AppBarComponent from "./components/Appbar/Appbar"
const App = () => {
  return (
    // <AuthProvider>
    //   <ConfirmProvider>
        <Router>
          <AppRouter></AppRouter>
          <AppBarComponent />
        </Router>
    //   </ConfirmProvider>
    // </AuthProvider>
  );
};

export default App
