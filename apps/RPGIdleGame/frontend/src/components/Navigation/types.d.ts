declare type NavigationButtonComponentProps = {
    text: string,
    to: string,
    color?: 'inherit'|'primary'|'secondary'|'success'|'error'|'info'|'warning',
    variant?: 'contained'|'outlined'|'text',
}

declare type NavigationIconComponentProps = {
    // @ts-ignore
    icon: SvgIconComponent,
    label: string,
    to: string,
};
