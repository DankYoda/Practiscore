import {Component, OnInit} from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {Menubar} from "primeng/menubar";
import {MenuItem} from "primeng/api";

@Component({
  selector: 'app-root',
    imports: [RouterOutlet, Menubar],
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
                icon: 'pi pi-fw pi-file',
                routerLink: '/home',
            },
            {
                label: 'Scores',
                icon: 'pi pi-fw pi-pencil',
                routerLink: '/scores'
            },
            {
                label: 'Events',
                icon: 'pi pi-fw pi-question',
                routerLink: '/events',
                command: () => {
                  // Execute a function when this item is clicked
                  console.log('Help clicked!');
                }
            },
            {
                label: 'Matches',
                icon: 'pi pi-fw pi-info',
                routerLink: ['/matches']
            },
            {
                label: 'Support',
                icon: 'pi pi-fw pi-info',
                routerLink: ['/support']
            }
        ];
    }
}
