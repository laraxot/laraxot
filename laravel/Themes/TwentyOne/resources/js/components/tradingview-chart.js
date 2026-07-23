/**
 * TradingView Lightweight Charts Component
 * 
 * Candlestick chart per historical prices con volume bars
 * 
 * @module Themes/TwentyOne/resources/js/components
 * @requires lightweight-charts
 */

import { createChart, ColorType, CrosshairMode } from 'lightweight-charts';

/**
 * Configurazione default del chart
 */
const defaultChartOptions = {
    layout: {
        background: { type: ColorType.Solid, color: '#0f172a' }, // slate-900
        textColor: '#94a3b8', // slate-400
        fontSize: 12,
        fontFamily: 'Inter, system-ui, sans-serif',
    },
    grid: {
        vertLines: {
            color: 'rgba(148, 163, 184, 0.1)', // slate-400/10
            style: 3, // Dotted
        },
        horzLines: {
            color: 'rgba(148, 163, 184, 0.1)',
            style: 3,
        },
    },
    crosshair: {
        mode: CrosshairMode.Normal,
        vertLine: {
            width: 1,
            color: '#10b981', // emerald-500
            style: 3,
            labelBackgroundColor: '#10b981',
        },
        horzLine: {
            width: 1,
            color: '#10b981',
            style: 3,
            labelBackgroundColor: '#10b981',
        },
    },
    rightPriceScale: {
        borderColor: 'rgba(148, 163, 184, 0.2)',
        scaleMargins: {
            top: 0.1,
            bottom: 0.2, // Space for volume
        },
    },
    timeScale: {
        borderColor: 'rgba(148, 163, 184, 0.2)',
        timeVisible: true,
        secondsVisible: false,
    },
};

/**
 * Configurazione default delle serie candlestick
 */
const defaultCandlestickOptions = {
    upColor: '#10b981', // emerald-500
    downColor: '#ef4444', // red-500
    borderVisible: false,
    wickUpColor: '#10b981',
    wickDownColor: '#ef4444',
};

/**
 * Configurazione default delle serie volume
 */
const defaultVolumeOptions = {
    priceFormat: {
        type: 'volume',
    },
    priceScaleId: '', // Overlay sul chart principale
    scaleMargins: {
        top: 0.85, // Volume nella parte inferiore
        bottom: 0,
    },
    upColor: 'rgba(16, 185, 129, 0.5)', // emerald-500/50
    downColor: 'rgba(239, 68, 68, 0.5)', // red-500/50
};

/**
 * Crea un chart TradingView candlestick con volume
 * 
 * @param {string|HTMLElement} container - Container element o selector
 * @param {Object} options - Opzioni custom
 * @returns {Object} Chart instance con metodi utili
 */
export function createTradingViewChart(container, options = {}) {
    const element = typeof container === 'string' 
        ? document.querySelector(container) 
        : container;

    if (!element) {
        console.error('TradingView Chart: Container non trovato', container);
        return null;
    }

    // Merge opzioni
    const chartOptions = {
        ...defaultChartOptions,
        ...options.chartOptions,
        width: options.width || element.clientWidth,
        height: options.height || 400,
    };

    // Crea chart
    const chart = createChart(element, chartOptions);

    // Aggiungi serie candlestick
    const candlestickSeries = chart.addCandlestickSeries({
        ...defaultCandlestickOptions,
        ...options.candlestickOptions,
    });

    // Aggiungi serie volume
    const volumeSeries = chart.addHistogramSeries({
        ...defaultVolumeOptions,
        ...options.volumeOptions,
    });

    // Responsive resize
    const resizeObserver = new ResizeObserver((entries) => {
        for (const entry of entries) {
            const { width } = entry.contentRect;
            chart.applyOptions({ width });
        }
    });

    resizeObserver.observe(element);

    // Metodi pubblici
    return {
        /**
         * Aggiorna i dati del chart
         * @param {Array} candlestickData - [{ time, open, high, low, close }]
         * @param {Array} volumeData - [{ time, value, color }]
         */
        updateData(candlestickData, volumeData = []) {
            candlestickSeries.setData(candlestickData);
            volumeSeries.setData(volumeData);
        },

        /**
         * Aggiungi un nuovo candlestick
         * @param {Object} candle - { time, open, high, low, close }
         */
        addCandle(candle) {
            candlestickSeries.update(candle);
        },

        /**
         * Aggiungi un volume bar
         * @param {Object} volume - { time, value, color }
         */
        addVolume(volume) {
            volumeSeries.update(volume);
        },

        /**
         * Fit contents (zoom to fit)
         */
        fitContent() {
            chart.timeScale().fitContent();
        },

        /**
         * Vai a un time specifico
         * @param {number} time - Unix timestamp
         */
        scrollTo(time) {
            chart.timeScale().scrollToPosition(time, false);
        },

        /**
         * Distruggi chart e cleanup
         */
        destroy() {
            resizeObserver.disconnect();
            chart.remove();
        },

        // Accesso diretto alle serie per customizzazioni
        chart,
        candlestickSeries,
        volumeSeries,
    };
}

/**
 * Formatta i dati dal backend per lightweight-charts
 * 
 * @param {Array} priceHistory - Dati dal backend
 * @returns {Object} { candlestickData, volumeData }
 */
export function formatChartData(priceHistory) {
    const candlestickData = [];
    const volumeData = [];

    if (!priceHistory || !Array.isArray(priceHistory)) {
        return { candlestickData: [], volumeData: [] };
    }

    priceHistory.forEach((item) => {
        const time = new Date(item.created_at).getTime() / 1000;
        const price = parseFloat(item.price) || 0;
        const volume = parseFloat(item.volume) || 0;

        // Candlestick (usiamo lo stesso price per OHLC per ora)
        // TODO: Quando avremo dati reali, usare open/high/low/close
        candlestickData.push({
            time,
            open: price,
            high: price * 1.02, // +2%
            low: price * 0.98,  // -2%
            close: price,
        });

        // Volume bar
        const isUp = volume >= 0;
        volumeData.push({
            time,
            value: Math.abs(volume),
            color: isUp ? 'rgba(16, 185, 129, 0.5)' : 'rgba(239, 68, 68, 0.5)',
        });
    });

    return { candlestickData, volumeData };
}

/**
 * Inizializza tutti i chart TradingView nella pagina
 * Supporta data-attributes per configurazione declarativa
 * 
 * Usage:
 * <div 
 *     data-tradingview-chart
 *     data-chart-width="800"
 *     data-chart-height="400"
 *     data-chart-data='[...]'
 * ></div>
 */
export function initTradingViewCharts() {
    const chartElements = document.querySelectorAll('[data-tradingview-chart]');

    chartElements.forEach((element) => {
        const width = parseInt(element.dataset.chartWidth) || undefined;
        const height = parseInt(element.dataset.chartHeight) || 400;
        const data = element.dataset.chartData 
            ? JSON.parse(element.dataset.chartData) 
            : [];

        const chart = createTradingViewChart(element, { width, height });

        if (chart && data.length > 0) {
            const { candlestickData, volumeData } = formatChartData(data);
            chart.updateData(candlestickData, volumeData);
        }
    });
}

// Auto-init quando il DOM è pronto
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', initTradingViewCharts);
}

export default createTradingViewChart;
