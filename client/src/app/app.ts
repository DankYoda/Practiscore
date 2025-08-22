import {Component, OnInit} from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {Menubar} from "primeng/menubar";
import {MenuItem} from "primeng/api";
import {NgClass, NgIf} from "@angular/common";
import {Badge} from "primeng/badge";
import {Ripple} from "primeng/ripple";
import {Avatar} from "primeng/avatar";
import {InputText} from "primeng/inputtext";

@Component({
    selector: 'app-root',
    imports: [RouterOutlet, Menubar, Ripple],
    templateUrl: './app.html',
    styleUrl: './app.css'
})
export class App implements OnInit {
    protected title = 'client';
    items: MenuItem[] | undefined;
    ngOnInit() {
        this.items =
        [
            {
                label: 'Home',
                routerLink: '/home',
            },
            {
                label: 'Scores',
                routerLink: '/scores'
            },
            {
                label: 'Events',
                routerLink: '/events',
                command: () => {
                  // Execute a function when this item is clicked
                  console.log('Help clicked!');
                }
            },
            {
                label: 'Matches',
                routerLink: ['/matches']
            },
            {
                label: 'Support',
                routerLink: ['/support']
            }
        ];
    }
}
