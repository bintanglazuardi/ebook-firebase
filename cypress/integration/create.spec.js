
describe('Tambah Buku', () => {
    it('Open localhost', () => {
        cy.visit('http://localhost:8000/ebooks')
        cy.wait(2500)
        cy.url().should('eq', 'http://localhost:8000/ebooks')
    })

    it('Input judul', () => {
        cy.get('#judul')
        .type('Praktikum Web')
        .should('have.value', 'Praktikum Web')
    })

    it('Input pengarang', () => {
        cy.get('#pengarang')
        .type('Sekolah Vokasi UGM')
        .should('have.value', 'Sekolah Vokasi UGM')
    })

    it('Click submit', () => {
        cy.get('#submitBook')
        .click({force: true})
    })

})