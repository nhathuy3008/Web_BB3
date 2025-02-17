import AppBar from "@mui/material/AppBar";
import Toolbar from "@mui/material/Toolbar";
import Box from "@mui/material/Box";
// import ShoppingCartCheckoutIcon from '@mui/icons-material/ShoppingCartCheckout';
import { Button } from "@mui/material";
// import { useAuth } from "./Account";
// import Profiles from "./Profile";
import ShoppingCartOutlinedIcon from '@mui/icons-material/ShoppingCartOutlined';

const NavBar = () => {
    // const { isAuthenticated } = useAuth()
    return (   
        <AppBar
            position="fixed"
            sx={{
                backgroundColor: "#fff",
                color: "#000",
                boxShadow: "0 4px 6px rgba(0, 0, 0, 0.1)",
            }}
        >
            <Toolbar
            sx={{
                backgroundColor: "#fff",
                justifyContent:"space-between",
                alignItems:"center",
            }}
            >
                <Box sx={{display:"flex",alignItems:"center"}}>
                    <Button href="/"><img 
                        src="https://res.cloudinary.com/dceicuawz/image/upload/v1734172951/ksejfedsxueanb1ohrr1.png"
                        style={{ height: "80px",width:"auto", marginRight:"20px" }}
                        alt="Agoda Logo"/>
                    </Button>
                    <Button href="/" sx={{color:"#000",textTransform:"none",fontSize:16,margin:"10px"}}>
                        Máy bay + khách sạn
                    </Button>
                    <Button href="/account/Product" sx={{color:"#000",textTransform:"none",fontSize:16,margin:"10px"}}>
                        Chổ ở
                    </Button>
                    <Button href="/Activity" sx={{color:"#000",textTransform:"none",fontSize:16,margin:"10px"}}>
                        Hoạt động
                    </Button>
                    <Button href="/Discount" sx={{color:"#000",textTransform:"none",fontSize:16,margin:"10px"}}>
                        Phiếu giảm giá và ưu đãi
                    </Button>
                    <Button sx={{color:"#000",textTransform:"none",fontSize:16,margin:"10px"}}>
                        Du lịch
                    </Button>
                </Box>
                <Box sx={{display:"flex"}}>
                    {/* {isAuthenticated ? (
                        <>
                            <Button href="/account/ShoppingCart" sx={{ color: "#000", textTransform: "none" }}>
                                <ShoppingCartOutlinedIcon />
                            </Button>
                            <Profiles fontSize="30px" />
                        </>
                    ) : (
                    <> */}
                    <Button href="/account/login" sx={{color:"#000",textTransform:"none",fontSize:16}}>
                        Đăng nhập
                    </Button>
                    <Button href="/account/SignUp" sx={{color:"#000",textTransform:"none",fontSize:16,border:"1px solid black",borderRadius:"20px",marginLeft:"20px"}}>
                        Tạo tài khoản
                    </Button>
                    <Button sx={{color:"#000",textTransform:"none",fontSize:16}}>
                        {/* <ShoppingCartCheckoutIcon/> */}
                    </Button>
                    {/* </> */}
                {/* )} */}
                </Box>
            </Toolbar>
        </AppBar>
    );
}

export default NavBar;
