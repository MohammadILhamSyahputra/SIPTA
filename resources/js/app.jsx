import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import CashierMenu from './components/CashierMenu';

const container = document.getElementById('cashier-app');
if (container) {
    const root = createRoot(container);
    root.render(<CashierMenu />);
}