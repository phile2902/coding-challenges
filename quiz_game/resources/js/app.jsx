import React from "react";
import { createRoot } from "react-dom/client";
import "@fontsource/roboto/300.css";
import "@fontsource/roboto/400.css";
import "@fontsource/roboto/500.css";
import "@fontsource/roboto/700.css";
import CssBaseline from "@mui/material/CssBaseline";

import Home from "./Home";
import "./bootstrap";

const root = createRoot(document.getElementById("app"));
root.render(
    <>
        <Home />
        <CssBaseline />
    </>,
);
