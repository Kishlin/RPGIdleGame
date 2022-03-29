import { render, unmountComponentAtNode } from "react-dom";
import { act } from "react-dom/test-utils";
import React from 'react';

import App from './App';

jest.mock('./api/character/allCharacters', (): AllCharactersApi => {
    return (onResponse) => onResponse([]);
});

let container: HTMLElementTagNameMap['div'] = null;

beforeEach(() => {
    // setup a DOM element as a render target
    container = document.createElement("div");
    document.body.appendChild(container);
});

afterEach(() => {
    // cleanup on exiting
    unmountComponentAtNode(container);
    container.remove();
    container = null;
});

test('renders the loader on first mount', () => {
    act(() => {
        render(
            <App />,
            container,
        );
    })

    const linkElement = container.querySelector('div.loader');
    expect(linkElement).toBeInTheDocument();
});
