import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import Home from './Home.jsx';

const root = createRoot(document.getElementById('app'));
root.render(<Home />);
